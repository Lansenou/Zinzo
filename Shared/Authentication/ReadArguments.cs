using System.Collections;
using UnityEngine;
using UnityEngine.Events;

namespace Shared.Authentication
{
    public class ReadArguments : MonoBehaviour
    {
        public OnAuthenticationArgument OnArgument;
        public UnityEvent OnNoArgument;

        private string cmdInfo = "";
        private string username;
        private string token;
        private int userId = 0;

        IEnumerator Start()
        {
            string[] arguments = System.Environment.GetCommandLineArgs();

            yield return null;

            for (int i = 0; i < arguments.Length; i++)
            {
                if (arguments[i] == CommandlineArguments.Username)
                {
                    cmdInfo += "Username: " + (username = arguments[i + 1]) + "\n";                    
                    continue;
                }

                if (arguments[i] == CommandlineArguments.Token)
                {
                    cmdInfo += "Token: " + (token = arguments[i + 1]) + "\n";
                    continue;
                }

                if (arguments[i] == CommandlineArguments.UserId)
                {
                    cmdInfo += "User Id: " + arguments[i + 1] + "\n";
                    userId = int.Parse(arguments[i + 1]);
                    continue;
                }
            }

            if (!string.IsNullOrEmpty(username) && !string.IsNullOrEmpty(token) && userId != 0)
            {
                OnArgument.Invoke(username, token, userId);
            }

            if (!string.IsNullOrEmpty(cmdInfo))
            {
                Debug.Log(cmdInfo);
            }
            else
            {
                OnNoArgument.Invoke();
                Debug.Log("Not authenticating automatically");
            }
        }
    }
}
