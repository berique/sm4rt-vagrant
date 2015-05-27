Vagrant.configure(2) do |config|
  config.vm.box = "hashicorp/precise64"
  if Vagrant.has_plugin?("vagrant-cachier")
    config.cache.scope = :box
  end
  config.vm.define "bd", primary: true do |bd|
    bd.vm.network "private_network", ip: "192.168.50.5"
    # Shell Script para instalação do Banco de Dados
    bd.vm.provision "shell", inline: <<SCRIPT
BDSENHA=123456
echo "Configurando o DNS do GOOGLE"
echo "nameserver 8.8.8.8" | sudo tee /etc/resolv.conf > /dev/null
echo "Atualizando a lista de pacotes."
apt-get -qq update
echo "Configurando a senha do banco de dados como: $BDSENHA"
echo "mysql-server mysql-server/root_password password $BDSENHA" | debconf-set-selections
echo "mysql-server mysql-server/root_password_again password $BDSENHA" | debconf-set-selections
apt-get -y install mysql-server
sed -i "s/^bind-address/#bind-address/" /etc/mysql/my.cnf
echo "Criando o banco de dados: sm4rtchange"
mysql -uroot -p$BDSENHA -e "CREATE DATABASE sm4rtchange"
mysql -uroot -p$BDSENHA -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '$BDSENHA'"
echo "Criando o tabela: pessoa"
mysql -uroot -p$BDSENHA sm4rtchange -e "CREATE TABLE pessoas (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, nome VARCHAR(255) NOT NULL)"
echo "Inserindo o Pedro na tabela 'pessoa'."
mysql -uroot -p$BDSENHA sm4rtchange -e "INSERT INTO pessoas (nome) VALUES('Pedro')"
service mysql restart
SCRIPT
  end

  config.vm.define "web" do |web|
    web.vm.network "private_network", ip: "192.168.50.4"
    # Shell Script para instalação do Apache e PHP
    web.vm.provision "shell", inline: <<SCRIPT
BDSENHA=123456
echo "Configurando o DNS do GOOGLE"
echo "nameserver 8.8.8.8" | sudo tee /etc/resolv.conf > /dev/null
echo "Atualizando a lista de pacotes."
apt-get -qq update
echo "Instalando o PHP5, Apache 2 e bibliotecas."
apt-get -y install php5 php5-gd php5-mysql php5-dev mysql-client-5.5 libcurl3-openssl-dev libpcre3-dev apache2 libapache2-mod-php5
a2enmod rewrite > /dev/null 2>&1
echo "Permite que o Apache sobrescreva as configurações."
sed -i "s/AllowOverride None/AllowOverride All/g" /etc/apache2/apache2.conf
rm -rf /var/www
ln -fs /vagrant/public /var/www
service apache2 restart
SCRIPT
  end

end
