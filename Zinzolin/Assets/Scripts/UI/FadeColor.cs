using UnityEngine;
using UnityEngine.UI;

namespace UI
{
    public class FadeColor : FadeIn
    {
        [SerializeField]
        protected Color startColor = Color.white;
        [SerializeField]
        protected Color targetColor = Color.black;

        [SerializeField]
        protected bool useAlpha = true;

        public override void Fade(Graphic graphic)
        {
            graphic.CrossFadeColor(startColor, 0, shouldIgnoreTimescale, useAlpha);
            graphic.CrossFadeColor(targetColor, fadeTime, shouldIgnoreTimescale, useAlpha);
        }
    }
}

