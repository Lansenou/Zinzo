using Shared.Authentication;
using System.Collections;
using UnityEngine;
using UnityEngine.UI;

namespace Authentication
{
    public class Login : MonoBehaviour
    {
        [SerializeField]
        private string loginUrl = @"https://lansenou.com/api/login";

        [SerializeField]
        private InputField usernameField;

        [SerializeField]
        private InputField passwordField;

        [SerializeField]
        private UnityEngine.Events.UnityEvent OnLogin;

        public void StartLogin()
        {
            StartCoroutine(LoggingIn());
        }

        IEnumerator LoggingIn()
        {
            WWWForm form = new WWWForm();
            form.AddField("username", usernameField.text);
            form.AddField("password", passwordField.text);

            WWW www = new WWW(loginUrl, form);
            yield return www;

            LoginResult result = WWWHelper.ReadFromWWW<LoginResult>(www);
            if (result.wasSuccess)
            {
                SessionManager.SetUser(new User(usernameField.text, passwordField.text, result.userID));
                OnLogin.Invoke();
            }
            else
            {
                Debug.LogError(result.errorMessage);
            }
        }
    }
}



