using System;
using System.Windows.Forms;

namespace Launcher
{
    public partial class PleaseWaitForm : Form
    {
        private Action callback;
        private bool closing = false;

        public PleaseWaitForm()
        {
            InitializeComponent();
            FormClosing += button1_Click;
        }

        public void Initialize(string title, string desc, Action action = null)
        {
            Text = title;
            textBox1.Text = desc;
            callback = action;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (!closing)
            {
                closing = true;
                if (callback != null)
                {
                    callback.Invoke();
                }
                Close();
            }

        }
    }
}
