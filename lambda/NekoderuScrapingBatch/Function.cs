using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

using Amazon.Lambda.Core;
using Amazon.Lambda.Serialization;

// Assembly attribute to enable the Lambda function's JSON input to be converted into a .NET class.
[assembly: LambdaSerializerAttribute(typeof(Amazon.Lambda.Serialization.Json.JsonSerializer))]

namespace NekoderuScrapingBatch
{
    public class Function
    {
        private ILambdaContext Context;

        /// <summary>
        /// Nekoderuスクレイピング用のLambdaエントリポイント
        /// </summary>
        /// <param name="input">CloudWatchのスケジュールイベント引数</param>
        /// <param name="context">Lambdaの実行コンテキスト</param>
        /// <returns></returns>
        public NekoderuScrapingBatch.NekoData FunctionHandler(Object input, ILambdaContext context)
        {
            context.Logger.LogLine("NekoderuBatch実行開始！");

            this.Context = context;
            NekoderuScrapingBatch model = new NekoderuScrapingBatch(context);
            NekoderuScrapingBatch.NekoData ret = null;
            try {
                Newtonsoft.Json.Linq.JObject obj = (Newtonsoft.Json.Linq.JObject)input;
                DateTime stddt = obj.Value<DateTime>("time");

                context.Logger.LogLine("実行日:" + stddt.ToString("yyyy/MM/dd"));

                ret = model.RunMaigoNekoFromKumamotoAnimalCenter(stddt);
                model.SendMaigoNekoInfo(ret);
            } catch (Exception ex) {
                context.Logger.LogLine("NekoderuBatch実行中にエラー発生！");
                context.Logger.LogLine(ex.ToString());
            }

            context.Logger.LogLine("NekoderuBatch実行終了！");
            return ret;
        }
    }
}
