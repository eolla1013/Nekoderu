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
        /// Nekoderu�X�N���C�s���O�p��Lambda�G���g���|�C���g
        /// </summary>
        /// <param name="input">CloudWatch�̃X�P�W���[���C�x���g����</param>
        /// <param name="context">Lambda�̎��s�R���e�L�X�g</param>
        /// <returns></returns>
        public NekoderuScrapingBatch.NekoData FunctionHandler(Object input, ILambdaContext context)
        {
            context.Logger.LogLine("NekoderuBatch���s�J�n�I");

            this.Context = context;
            NekoderuScrapingBatch model = new NekoderuScrapingBatch(context);
            NekoderuScrapingBatch.NekoData ret = null;
            try {
                Newtonsoft.Json.Linq.JObject obj = (Newtonsoft.Json.Linq.JObject)input;
                DateTime stddt = obj.Value<DateTime>("time");

                context.Logger.LogLine("���s��:" + stddt.ToString("yyyy/MM/dd"));

                ret = model.RunMaigoNekoFromKumamotoAnimalCenter(stddt);
                if (ret.NekoList.Count > 0) {
                    model.SendMaigoNekoInfo(ret);
                } else {
                    context.Logger.LogLine("�o�^�f�[�^�Ȃ�");
                }
            } catch (Exception ex) {
                context.Logger.LogLine("NekoderuBatch���s���ɃG���[�����I");
                context.Logger.LogLine(ex.ToString());
            }

            context.Logger.LogLine("NekoderuBatch���s�I���I");
            return ret;
        }
    }
}
