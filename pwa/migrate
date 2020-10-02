#!/usr/bin/python3

# Module Imports
import mariadb
import sys
import os
import fnmatch
import json
from time import sleep

HOST = 'localhost'
PORT = 3306

try: 
    config = {}
    with open(sys.path[0]+'/migrate.cfg', 'r') as f: 
        config = json.load(f)
        HOST = config['HOST']
        PORT = config['PORT']
        print(f"Config file found:\n -> Host: {HOST}\n -> Port: {PORT}")

except:
    print(f"No migrate.config file or an invalid one found, using defaults:\n -> Host: {HOST}\n -> Port: {PORT}")


def runMigration(completed):

    path = sys.path[0] + '/sql'
    files = sorted(set(fnmatch.filter(os.listdir(path), '*.sql'))-set(completed))

    if not files: 
        print('\033[92mThere are no new migrations!\033[0m')
        sys.exit(1)

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
                    print(file)
                    cur.execute(
                        "INSERT INTO iot.migrations(migration) VALUES (?)", (file,))
                    conn.commit()
                    print(f"\033[92m{file} executed!\033[0m")
                    sleep(0.1)
                    i+=1
                else:
                    conn.rollback()
        else:
            break

    print(f"{i}/{len(files)} migrations were executed")

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
        host=HOST,
        port=PORT,
        database="iot"
    )
    conn.autocommit = False
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

cur.close()
conn.close()
print('done!')
