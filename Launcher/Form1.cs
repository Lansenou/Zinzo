using Shared.Authentication;
using System;
using System.Collections.Generic;
using Newtonsoft.Json;
using System.Windows.Forms;
using System.Threading.Tasks;

namespace Launcher
{
    public partial class Form1 : Form
    {
        private string loginUrl = @"https://lansenou.com/api/login";

        public Form1()
        {
            InitializeComponent();
            NotifyIconManager.Icon.Click += NotifyIconInstance_Click;
        }

        private void NotifyIconInstance_Click(object sender, EventArgs e)
        {
            Console.WriteLine("Click");
            Form form = GetActiveForm();
            if (form != null)
            {
                form.Activate();
            }
        }

        private Form GetActiveForm()             
        {
            FormCollection collection = Application.OpenForms;
            for (int i = 0; i < collection.Count; i++)
            {
                if (collection[i].Visible) return collection[i];
            }
            return null;
        }


        private async void button1_Click(object sender, EventArgs e)
        {
            // Start Dialog
            PleaseWaitForm pleaseWait = new PleaseWaitForm();
            pleaseWait.Initialize("Logging In", "Please Wait!", () => { Console.Write("IDK HOW TO CANCEL ASYNC"); });
            pleaseWait.Show();

            await Login();

            pleaseWait.Hide();
        }

        private async Task Login()
        {
            // Post Request
            Dictionary<string, string> pairs = new Dictionary<string, string>();
            pairs.Add("username", usernameBox.Text);
            pairs.Add("password", passwordBox.Text);
            string result = await HttpHelper.MakePostRequest(loginUrl, pairs);

            passwordBox.Clear();

            // Json Deserialize
            JsonSerializerSettings settings = new JsonSerializerSettings();
            settings.NullValueHandling = NullValueHandling.Ignore;
            LoginResult loginResult = JsonConvert.DeserializeObject<LoginResult>(result, settings);

            // Log user in
            if (loginResult.wasSuccess)
            {
                User user = new User(usernameBox.Text, loginResult.userToken, loginResult.userID);
                Form2 form2 = new Form2();

                // Load launcher
                form2.SetUser(user);
                form2.Show();
                form2.FormClosed += (object sender, FormClosedEventArgs e) => { Show();  Activate(); };
                Hide();
            }
            else
            {
                MessageBox.Show(loginResult.errorMessage, "Failed to login", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }


        private void button2_Click(object sender, EventArgs e)
        {
            System.Diagnostics.Process.Start("https://lansenou.com/api/docs/register");
        }
    }
}
