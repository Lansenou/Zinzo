using Shared.Authentication;
using System;
using System.Diagnostics;
using System.Windows.Forms;
using System.IO;

namespace Launcher
{
    public partial class Form2 : Form
    {
        private User user;

        public Form2()
        {
            InitializeComponent();
        }

        public void SetUser(User user)
        {
            this.user = user;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (user == null)
            {
                return;
            }

            // TODO: http://stackoverflow.com/questions/17908993/starting-a-process-with-a-user-name-and-password
            string arguments = string.Format("{0} {1} ", CommandlineArguments.Username, user.Username);
            arguments += string.Format("{0} {1} ", CommandlineArguments.Token, user.Token);
            arguments += string.Format("{0} {1} ", CommandlineArguments.UserId, user.UserId);

            ProcessStartInfo info = new ProcessStartInfo("Zinzolin.exe", arguments);
            info.WorkingDirectory = Path.GetFullPath(Path.Combine(Directory.GetCurrentDirectory(), @"..\..\..\Zinzolin\Build\"));
            Process game = Process.Start(info);
        }
    }
}
