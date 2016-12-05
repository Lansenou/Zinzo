using UnityEngine;
using System.Collections;
using Shared.Authentication;

namespace Authentication
{
    public class SessionManager
    {
        public static User currentUser = null;

        public static void SetUser(User user)
        {
            Debug.Log("New user is: " + user.Username);
            currentUser = user;
        }
    }
}

