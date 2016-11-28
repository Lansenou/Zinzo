using Shared.Authentication;
using System;
using System.Collections.Generic;
using System.Windows.Forms;
using Newtonsoft.Json;
using System.Diagnostics;
using System.IO;

namespace Launcher
{
    public partial class Login : Form
    {
        private string loginUrl = @"https://lansenou.com/api/login";

        public Login()
        {
            InitializeComponent();
        }

        private async void button1_Click(object sender, EventArgs e)
        {
            Dictionary<string, string> pairs = new Dictionary<string, string>();
            pairs.Add("username", usernameBox.Text);
            pairs.Add("password", passwordBox.Text);
            string result = await HttpHelper.MakePostRequest(loginUrl, pairs);

            LoginResult loginResult = JsonConvert.DeserializeObject<LoginResult>(result);

            if (loginResult.wasSuccess)
            {
                User user = new User(usernameBox.Text, loginResult.userToken, loginResult.userID);
                //Form2 form2 = new Form2();
                //form2.SetUser(user);
                //form2.Show();
                //Close();

                string arguments = string.Format("{0} {1} ", CommandlineArguments.Username, user.Username);
                arguments += string.Format("{0} {1} ", CommandlineArguments.Token, user.Token);
                arguments += string.Format("{0} {1} ", CommandlineArguments.UserId, user.UserId);

                ProcessStartInfo info = new ProcessStartInfo("Zinzolin.exe", arguments);

                info.WorkingDirectory = Path.GetFullPath(Path.Combine(Directory.GetCurrentDirectory(), @"..\..\..\Zinzolin\Build\"));
                Process game = Process.Start(info);
            }

            Console.Write(result);
        }

        private void button2_Click(object sender, EventArgs e)
        {
            System.Diagnostics.Process.Start("https://lansenou.com/api/docs/register");
        }
    }
}
