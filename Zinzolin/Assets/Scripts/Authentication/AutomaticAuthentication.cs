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
            form.AddField("userID", token);

            WWW www = new WWW(verificationUrl, form);
            yield return www;

            VerificationResult result = WWWHelper.ReadFromWWW<VerificationResult>(www);
            if (result.wasSuccess)
            {
                OnAuthenticate.Invoke(username, token, userId);
            }
            else
            {
                Debug.LogError(result.errorMessage);
            }
        }
    }
}
