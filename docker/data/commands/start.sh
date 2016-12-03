source ../variables.sh

declare -a ServicesList=("apache2" "mysql" "cron")

for i in "${ServicesList[@]}"
    do
        if service --status-all | grep -Fq $i; then 
            service $i restart #&>/dev/null
        fi
done
