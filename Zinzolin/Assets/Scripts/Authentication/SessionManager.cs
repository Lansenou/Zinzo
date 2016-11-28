using UnityEngine;
using System.Collections;
using Shared.Authentication;

namespace Authentication
{
    public class SessionManager
    {
        public static User currentUser = null;

        public static void AddUser(User user)
        {
            currentUser = user;
        }
    }
}

