#
# Cookbook Name:: phpenv
# Recipe:: default
#
# Copyright 2015, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#

# Timezoneを日本時間(JST)にする
link "/etc/localtime" do
  to "/usr/share/zoneinfo/Japan"
end

# ロケールの設定
execute "localectl set-locale LANG=ja_JP.utf8" do
  command "localectl set-locale LANG=ja_JP.utf8"
  action :run
end

execute "localectl set-keymap jp106" do
  command "localectl set-keymap jp106"
  action :run
end

