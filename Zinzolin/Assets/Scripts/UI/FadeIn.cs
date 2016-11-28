using UnityEngine;
using UnityEngine.UI;

namespace UI
{
    public class FadeIn : MonoBehaviour
    {
        [SerializeField]
        bool fadeChildren = false;
        [SerializeField]
        float fadeTime = 1;
        [SerializeField]
        bool shouldIgnoreTimescale = false;

        // Use this for initialization
        void Start()
        {
            if (fadeChildren)
            {
                Graphic[] graphics = GetComponentsInChildren<Graphic>();
                foreach(Graphic graphic in graphics)
                {
                    Fade(graphic);
                }
            }
            else
            {
                Fade(GetComponent<Graphic>());
            }
        }

        void Fade(Graphic graphic)
        {
            graphic.CrossFadeAlpha(0, 0, true);
            graphic.CrossFadeAlpha(1, fadeTime, shouldIgnoreTimescale);
        }
    }
}

