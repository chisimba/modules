aptitude update
aptitude safe-upgrade -y
aptitude install -y build-essential libssl-dev git-core
wget http://nodejs.org/dist/node-v0.4.7.tar.gz
tar xzf node-v0.4.7.tar.gz
rm -f node-v0.4.7.tar.gz
cd node-v0.4.7
./configure
make
make install
cd ..
rm -rf node-v0.4.7
curl http://npmjs.org/install.sh | sh
npm install socket.io paperboy
git clone https://github.com/charlvn/nodechat.git
cd nodechat
node server.js
