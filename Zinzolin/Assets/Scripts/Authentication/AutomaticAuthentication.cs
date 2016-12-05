using Shared.Authentication;
using System.Collections;
using UnityEngine;

namespace Authentication
{
    public class AutomaticAuthentication : MonoBehaviour
    {
        public OnAuthenticationArgument OnAuthenticate;

        private string verificationUrl = @"https://lansenou.com/api/verify";

        public void Authenticate(string username, string token, int userId)
        {
            StartCoroutine(Login(username, token, userId));
        }

        IEnumerator Login(string username, string token, int userId)
        {
            WWWForm form = new WWWForm();
            form.AddField("token", token);
            form.AddField("userID", userId);

            WWW www = new WWW(verificationUrl, form);
            yield return www;

            if (!string.IsNullOrEmpty(www.error))
            {
                Debug.LogError(username + token + www.error);
            }

            VerificationResult result = WWWHelper.ReadFromWWW<VerificationResult>(www);
            if (result.wasSuccess)
            {
                SessionManager.SetUser(new User(username, token, userId));
                OnAuthenticate.Invoke(username, token, userId);
            }
            else
            {
                //TODO: Inform user
                Debug.LogError(result.errorMessage + string.Format("[{0}],[{1}],[{2}]", username, token, userId));
            }
        }
    }
}
