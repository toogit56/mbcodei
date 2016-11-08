# php5.4,その他必要なプログラムのインストール
%w(curl php php-cli php-fpm php-pear php-curl php-mbstring php-pgsql php-devel zlib-devel pcre-devel php-xml).each do |p|
  package p do
    action :install
  end
end


# php-zipのインストール
execute "pecl install zip | grep 'Build process completed successfully'" do
  command "pecl install zip | grep 'Build process completed successfully'"
  action :run
  not_if "pecl list | grep 'zip'"
end

#
# php.ini にzip.soの追記
#
# ※後でテンプレートから読み込むようにする！
#
execute "sed -i -e \"s|;   extension=msql.so|   extension=zip.so|g\" /etc/php.ini" do
  command "sed -i -e \"s|;   extension=msql.so|   extension=zip.so|g\" /etc/php.ini"
  action :run
  only_if { File.exist? ("/etc/php.ini") }
end

