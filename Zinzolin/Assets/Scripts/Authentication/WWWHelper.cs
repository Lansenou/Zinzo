using UnityEngine;

namespace Authentication
{
    public class WWWHelper
    {
        public static T ReadFromWWW<T>(WWW www)
        {
            if (!string.IsNullOrEmpty(www.error))
            {
                Debug.LogError(www.error);
            }
            return ReadFromString<T>(www.text);
        }

        public static T ReadFromString<T>(string jsonString)
        {
            return JsonUtility.FromJson<T>(jsonString);
        }
    }
}
