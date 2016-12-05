using UnityEngine;
using UnityEngine.UI;

namespace UI
{
    public class FadeIn : MonoBehaviour
    {
        [SerializeField]
        protected bool fadeChildren = false;
        [SerializeField]
        protected float fadeTime = 1;
        [SerializeField]
        protected bool shouldIgnoreTimescale = false;

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

        public virtual void Fade(Graphic graphic)
        {
            graphic.CrossFadeAlpha(0, 0, true);
            graphic.CrossFadeAlpha(1, fadeTime, shouldIgnoreTimescale);
        }
    }
}

