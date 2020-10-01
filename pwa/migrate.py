# Module Imports
import mariadb
import sys
import os
import fnmatch

def runMigration(completed):

    path = sys.path[0] + '/sql'
    files = sorted(set(fnmatch.filter(os.listdir(path), '*.sql'))-set(completed))

    print('Scheduled migrations: ')
    for file in files:
        print(f"-> {file}")
    
    print("Do you want to execute these migrations? [Y/n]: ",end='')
    resp = input().rstrip()
    if resp != '' and resp != 'Y' and resp != 'y':
        print('Aborted!')
        return

    error = False
    i=0
    for file in files:
        if not error:
            with open(path + '/' + file, 'r') as f:
                sql = f.read().replace('\n', '').split(';')
                for q in sql:
                    if q and not error:
                        # print(q)
                        try:
                            cur.execute(q)
                        except mariadb.Error as e:
                            print(
                                f"\033[91mAn error occured while executing '{file}':\033[0m\n QUERY:{q}\n {e}")
                            error = True
                            break
                if not error:
                    cur.execute(
                        "INSERT INTO migrations(migration) VALUES (?)", (file,))
                    print(f"\033[92m{file} executed!\033[0m")
                    i+=1
        else:
            break

    print(f"{i}/{len(files)} migrations were executed")

    conn.close()
    print('done!')

def getCompletedMigrations():
    cur.execute(
        "SELECT migration FROM migrations"
    )

    completed = []
    for migration in cur:
        completed.append(migration[0])

    # for m in completed:
    #     print(f"->{m}")
    # if migrations could be queried, write data!
    return completed

# Connect to MariaDB Platform
try:
    conn = mariadb.connect(
        user="iot",
        password="2Cm&&G0CKKkt2@vL",
        host="bram-ubuntu.local",
        port=8889,
        database="iot"

    )
    print('Connection to db established')
except mariadb.Error as e:
    print(f"Error connecting to MariaDB Platform: {e}")
    sys.exit(1)

# Get Cursor
cur = conn.cursor()
try:
    completed = getCompletedMigrations()

    # if migrations could be queried, write data!
    runMigration(completed)

except mariadb.Error as e:
    print(f'An error occured: {e}')
    print('Attempting to create migration table...')
    try:
        cur.execute(
            '''CREATE TABLE migrations(
            id int auto_increment primary key,
            migration  varchar(200) not null,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
           )'''
        )
        print('Migrations table created')

        # execute migrations
        completed = getCompletedMigrations()
        runMigration(completed)

    except mariadb.Error as e:
        print(f'An error occured: {e}')


