import bcrypt
import mysql.connector

# Koneksi ke database
conn = mysql.connector.connect(
    host="homelaundry.my.id",
    user="homelaun_tumanina",
    password="Tumanina123",
    database="homelaun_tumanina"
)

cursor = conn.cursor()

# Data user baru
username = "kodok1"
password = "kodok123"

# Hash password
hashed_password = bcrypt.hashpw(password.encode(), bcrypt.gensalt())

# Insert ke database
query = "INSERT INTO user_web (username, password) VALUES (%s, %s)"
cursor.execute(query, (username, hashed_password.decode()))

# Commit perubahan
conn.commit()

print("User berhasil ditambahkan!")

# Tutup koneksi
cursor.close()
conn.close()
