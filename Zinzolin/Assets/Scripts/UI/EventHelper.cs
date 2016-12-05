using UnityEngine;
using System.Collections;

public class EventHelper : MonoBehaviour
{
    public void OpenUrl(string url)
    {
        Application.OpenURL(url);
    }
}
