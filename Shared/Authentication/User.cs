namespace Shared.Authentication
{
    [System.Serializable]
    public class User
    {
        public string Username { private set; get; }
        public string Token { private set; get; }
        public int UserId { private set; get; }

        public User(string username, string token, int userId)
        {
            if (userId != 0)
            {
                Username = username;
                Token = token;
                UserId = userId;
            }
        }
    }
}
