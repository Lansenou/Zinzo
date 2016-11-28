using UnityEngine;
using UnityEngine.UI;

namespace UI
{
    [RequireComponent(typeof(Text))]
    public class SetText : MonoBehaviour
    {
        public enum TextType
        {
            Username
        }

        [SerializeField]
        private TextType type;

        [SerializeField]
        private string prefix;

        [SerializeField]
        private string postfix;

        // Use this for initialization
        void Start()
        {
            GetComponent<Text>().text = prefix + GetText() + postfix;
        }

        string GetText()
        {
            switch (type)
            {
                case TextType.Username:
                    return Authentication.SessionManager.currentUser.Username;
                default:
                    return type.ToString();
            }
        }
    }
}

