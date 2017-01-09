using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using System.Net.Http;
using System.Xml;
using Amazon.Lambda.Core;
using Amazon.Lambda.Serialization;
using Newtonsoft.Json;

namespace NekoderuScrapingBatch
{
    public class NekoderuScrapingBatch
    {
        private ILambdaContext Context;

        public NekoderuScrapingBatch(ILambdaContext context) {
            this.Context = context;
        }

        public NekoData RunMaigoNekoFromKumamotoAnimalCenter(DateTime stddt) {
            NekoData neko = new NekoData();

            HttpClient cl = new HttpClient();
            Task<HttpResponseMessage> response = cl.GetAsync("http://doubutsuaigo.hinokuni-net.jp/dogscats/catsdetail.php?ymd=" + stddt.ToString("yyyyMMdd"));
            response.Wait(60000);
            if (response.IsCompleted) {
                if (response.Result.IsSuccessStatusCode) {
                    HttpContent content = response.Result.Content;
                    string strxml = content.ReadAsStringAsync().Result;
                    int stidx = strxml.IndexOf("<body>");
                    int edidx = strxml.IndexOf("</body>");
                    strxml = strxml.Substring(stidx, edidx - stidx + "</body>".Length);
                    strxml = strxml.Replace("&copy;", "(C)");
                    XmlDocument doc = new XmlDocument();
                    doc.LoadXml(strxml);
                    XmlNodeList headnodes = doc.SelectNodes(@"//div[@class='txtcolumn']");

                    neko.Title = string.Format("熊本市動物愛護センターで{0:yyyy年MM月dd日}に保護された猫です。", stddt);
                    Context.Logger.LogLine(neko.Title);
                    foreach (XmlNode headnode in headnodes) {
                        try {
                            NekoItem nekoitm = new NekoItem();

                            XmlNode node = headnode.SelectSingleNode(@"strong");
                            nekoitm.RangeText = node.InnerText;
                            Context.Logger.LogLine(nekoitm.RangeText);

                            node = headnode.SelectSingleNode(@"div/ul/li/a");
                            nekoitm.ImageUrl = @"http://doubutsuaigo.hinokuni-net.jp" + node.Attributes["href"].InnerText;
                            Context.Logger.LogLine(nekoitm.ImageUrl);

                            node = headnode.SelectSingleNode(@"div/dl");
                            System.Text.StringBuilder sb = new System.Text.StringBuilder();
                            System.Text.StringBuilder itmstr = new System.Text.StringBuilder();
                            foreach (XmlNode itm in node.ChildNodes) {
                                if (itm.Name == "dt" && sb.Length > 0) {
                                    sb.Append("、");
                                    if (itmstr.ToString().IndexOf("保護場所：") >= 0) {
                                        nekoitm.ShelterPlace = itmstr.ToString();
                                    }
                                    itmstr.Clear();
                                }
                                sb.Append(itm.InnerText);
                                itmstr.Append(itm.InnerText);
                            }
                            sb.Append("。");
                            sb.Replace(nekoitm.ShelterPlace + "、", "");
                            nekoitm.ShelterPlace = nekoitm.ShelterPlace.Replace("保護場所：", "");
                            nekoitm.Content = sb.ToString();
                            Context.Logger.LogLine(nekoitm.Content);

                            neko.NekoList.Add(nekoitm);
                        } catch (Exception ex) {
                            System.Diagnostics.Debug.WriteLine(ex.ToString());
                            Context.Logger.LogLine(ex.ToString());
                        }

                    }
                }
            }
            foreach (NekoItem itm in neko.NekoList) {
                response = cl.GetAsync(itm.ImageUrl, HttpCompletionOption.ResponseHeadersRead);
                response.Wait(60000);
                if (response.IsCompleted) {
                    if (response.Result.IsSuccessStatusCode) {
                        HttpContent content = response.Result.Content;
                        itm.ImageData = content.ReadAsByteArrayAsync().Result;
                    }
                }
            }

            return neko;
        }

        public void SendMaigoNekoInfo(NekoData neko) {
            HttpClient cl = new HttpClient();
            string token = "";
            MultipartFormDataContent tkncont = new MultipartFormDataContent();
            tkncont.Add(new StringContent(System.Environment.GetEnvironmentVariable("NEKODERU_EMAIL")), "email");
            tkncont.Add(new StringContent(System.Environment.GetEnvironmentVariable("NEKODERU_PWD")), "password");

            var tknresmsg = cl.PostAsync("https://neko.today/api/users/token.json", tkncont);
            tknresmsg.Wait(10000);
            if (tknresmsg.IsCompleted) {
                var tknres = tknresmsg.Result;
                Context.Logger.LogLine(tknres.StatusCode +":"+tknres.ReasonPhrase);
                string strres = tknres.Content.ReadAsStringAsync().Result;
                Context.Logger.LogLine(strres);
                Newtonsoft.Json.Linq.JObject obj = (Newtonsoft.Json.Linq.JObject)JsonConvert.DeserializeObject(strres);
                if ((bool)obj["success"]) {
                    token = (string)(obj["data"]["token"]);
                }
            }
            if (token == "") {
                Context.Logger.LogLine("トークンが取得できなかった。");
                return;
            }

            foreach (var itm in neko.NekoList) {
                MultipartFormDataContent reqcont = new MultipartFormDataContent();

                reqcont.Add(new StringContent(token), "token");
                reqcont.Add(new StringContent(""), "locate");
                reqcont.Add(new StringContent(itm.ShelterPlace), "address");
                reqcont.Add(new StringContent(itm.Content + itm.RangeText), "comment"); //TODO 元ページのURLがいる？コメント複数設定できたっけ？
                reqcont.Add(new StringContent(neko.Title), "name");
                reqcont.Add(new ByteArrayContent(itm.ImageData), "image[]", itm.ImageUrl);
                Context.Logger.LogLine("Content Count:" + reqcont.Count());
                Context.Logger.LogLine("");

                var response = cl.PostAsync("https://neko.today/api/cats/AddSheltered.json?token=" + token, reqcont);
                response.Wait(10000);
                if (response.IsCompleted) {
                    var res = response.Result;
                    Context.Logger.LogLine(res.StatusCode +":"+ res.ReasonPhrase);
                    Context.Logger.LogLine(res.Content.ReadAsStringAsync().Result);
                }
            }
        }

        public class NekoData
        {
            public string Title { get; set; }
            public List<NekoItem> NekoList { get; set; }

            public NekoData() {
                this.NekoList = new List<NekoItem>();
            }
        }

        public class NekoItem
        {
            public string RangeText { get; set; }
            public string ImageUrl { get; set; }
            public byte[] ImageData { get; set; }
            public string Content { get; set; }
            public string ShelterPlace { get; set; }

        }

    }
}
