namespace Shared.Authentication
{
    public class LoginResult
    {
        public bool wasSuccess = false;
        public string errorMessage = null;
        public int userID = 0;
        public string userToken = null;
    }
}
