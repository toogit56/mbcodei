# apacheのインストール
package "httpd" do
  action :install
end

#
# httpd.conf 設定
#
# ※httpd.confは後でテンプレートから読み込むようにする！
#
# httpd.conf のDocumentRoot変更
execute "sed -i -e 's|/var/www/html|/var/www/public|g' /etc/httpd/conf/httpd.conf" do
  command "sed -i -e 's|/var/www/html|/var/www/public|g' /etc/httpd/conf/httpd.conf"
  action :run
end

# httpd.conf AllowOverrideの変更
execute "sed -i -e '151s/AllowOverride None/AllowOverride All/g' /etc/httpd/conf/httpd.conf" do
  command "sed -i -e '151s/AllowOverride None/AllowOverride All/g' /etc/httpd/conf/httpd.conf"
  action :run
end

# javascriptがキャッシュされないように修正
execute "sed -i -e 's|#EnableMMAP off|EnableMMAP off|g' /etc/httpd/conf/httpd.conf" do
  command "sed -i -e 's|#EnableMMAP off|EnableMMAP off|g' /etc/httpd/conf/httpd.conf"
  action :run
end
execute "sed -i -e 's|EnableSendfile on|EnableSendfile off|g' /etc/httpd/conf/httpd.conf" do
  command "sed -i -e 's|EnableSendfile on|EnableSendfile off|g' /etc/httpd/conf/httpd.conf"
  action :run
end

# apacheのservice設定
# httpdを起動し、常時起動の設定も追加。
service "httpd" do
  supports :status => true, :restart => true
  action [ :enable, :restart ]
end

# firewallを停止
execute "systemctl stop firewalld" do
  command "systemctl stop firewalld"
  action :run
end

# firewallを常に無効にする
execute "systemctl disable firewalld" do
  command "systemctl disable firewalld"
  action :run
end

