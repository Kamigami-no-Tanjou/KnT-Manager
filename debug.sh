
exitfn () {
  trap SIGINT
  sudo ufw deny 35412
  exit
}

trap "exitfn" INT
sudo ufw allow 35412
symfony server:start --port=35412
