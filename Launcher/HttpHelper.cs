using System;
using System.Collections.Generic;
using System.Net.Http;
using System.Threading.Tasks;

namespace Launcher
{
    class HttpHelper
    {
        public static async Task<string> GetResponse(string requestUri)
        {
            string result = "";
            HttpClient httpClient = new HttpClient();

            try
            {
                HttpResponseMessage response = await httpClient.GetAsync(requestUri).ConfigureAwait(false);
                result = await response.Content.ReadAsStringAsync();
            }
            catch (HttpRequestException hre)
            {
                Console.WriteLine("hre.Message:" + hre.ToString());
            }
            return result;
        }

        public static async Task<string> MakePostRequest(string requestUrl, Dictionary<string, string> pairs)
        {
            HttpClient httpClient = new HttpClient();
            HttpContent httpContent = new FormUrlEncodedContent(pairs);
            string serverReply = "";

            try
            {
                HttpResponseMessage response = await httpClient.PostAsync(requestUrl, httpContent).ConfigureAwait(false);
                serverReply = await response.Content.ReadAsStringAsync();
            }
            catch (HttpRequestException hre)
            {
                Console.WriteLine("hre.Message:" + hre.ToString());
            }

            return (serverReply);
        }
    }
}
