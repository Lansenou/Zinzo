using UnityEngine.Events;

namespace Shared.Authentication
{
    [System.Serializable]
    public class OnAuthenticationArgument : UnityEvent<string, string, int> { }
}
