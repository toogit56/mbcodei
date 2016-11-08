#
# もっとスマートなやり方があると思う・・・でも今はこれで
#

# expectのインストール※対話式の入力を自動化してくれる
package "expect" do
  action :install
end

# postgresql9.2のインストール
%w(postgresql-server postgresql-contrib).each do |p|
  package p do
    action :install
  end
end

# PostgreSQLのデータディレクトリの作成
directory "/var/lib/pgsql/data" do
  action :nothing
  owner "postgres"
  group "postgres"
  recursive true
  mode 0700
  action :create
  not_if { Dir.exist? ("/var/lib/pgsql/data") }
end

# postgresqlの初期化
bash "initdb" do
  user "postgres"
  code <<-EOH
expect -c "
spawn initdb -D /var/lib/pgsql/data --pwprompt --auth=password
expect \\"Enter new superuser password:\\"
send -- \\"postgres\n\\"
expect \\"Enter it again:\\"
send -- \\"postgres\n\\"
expect \\"\\\$\\"
exit 0
"
  EOH
  action :run
  not_if { File.exist? ("/var/lib/pgsql/data/pg_hba.conf") }
end

#
# postgresql.conf のIP制限解除
#
# ※後でテンプレートから読み込むようにする！
#
execute "sed -i -e \"s|#listen_addresses = 'localhost'|listen_addresses = '*'|g\" /var/lib/pgsql/data/postgresql.conf" do
  command "sed -i -e \"s|#listen_addresses = 'localhost'|listen_addresses = '*'|g\" /var/lib/pgsql/data/postgresql.conf"
  action :run
  only_if { File.exist? ("/var/lib/pgsql/data/postgresql.conf") }
end

#
# pg_hba.conf に接続IP設定追加
#
# ※後でテンプレートから読み込むようにする！
#
execute "echo \"host all all 0.0.0.0/0 password\" >> /var/lib/pgsql/data/pg_hba.conf" do
  command "echo \"host all all 0.0.0.0/0 password\" >> /var/lib/pgsql/data/pg_hba.conf"
  action :run
  not_if "grep \"host all all 0.0.0.0/0 password\" /var/lib/pgsql/data/pg_hba.conf"
end

# postgresqlの起動、常時起動の設定も追加。
service "postgresql" do
  supports :status => true, :restart => true
  action [ :enable, :restart ]
end

#
# postgresqlユーザー作成
#
bash "create_user" do
  user "postgres"
  code <<-EOH
expect -c "
spawn createuser --username=postgres --createdb devmchuser --pwprompt
expect \\"Enter password for new role:\\"
send -- \\"devmchpasw\n\\"
expect \\"Enter it again:\\"
send -- \\"devmchpasw\n\\"
expect \\"Password:\\"
send -- \\"postgres\n\\"
expect \\"\\\$\\"
exit 0
"
  EOH
  action :run
end

#
# postgresqlDB作成
#
bash "create_database" do
  user "postgres"
  code <<-EOH
expect -c "
spawn createdb --username=devmchuser devmchdb
expect \\"Password:\\"
send -- \\"devmchpasw\n\\"
expect \\"\\\$\\"
exit 0
"
  EOH
  action :run
end
