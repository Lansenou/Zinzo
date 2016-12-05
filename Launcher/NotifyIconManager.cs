using System;
using System.Drawing;
using System.Windows.Forms;

namespace Launcher
{
    class NotifyIconManager
    {
        private static object _lock = new object();

        private static NotifyIcon notifyIcon;
        public static NotifyIcon Icon
        {
            get
            {
                lock (_lock)
                {
                    if (notifyIcon == null)
                    {
                        notifyIcon = CreateNotifyIcon();
                    }
                    return notifyIcon;
                }
            }
        }

        private static NotifyIcon CreateNotifyIcon()
        {
            NotifyIcon _notifyIcon = new NotifyIcon();
            _notifyIcon.Text = "Zinzolin Launcher";
            _notifyIcon.Icon = Properties.Resources.Icon;
            _notifyIcon.Visible = true;
            return _notifyIcon;
        }

        ~NotifyIconManager()
        {
            if (notifyIcon != null)
            {
                notifyIcon.Visible = false;
            }
        }
    }
}
