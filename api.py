import pymysql
from pymysql import Error

try:
    conn = pymysql.connect(
        user='root',
        password='PassyW0rdy!',
        host='localhost',
        database='HotelManager'
    )
except Error as e:
    print("Database connection error: {e}")

@app.route('/get_data', methods=['GET'])
def get_data():
    # Connect to the database
    cursor = conn.cursor()
    # Execute a query to get the data
    cursor.execute("SELECT * FROM API_TEST")
    data = cursor.fetchall()
    # Return the data
    return data


@app.route('/insert_data', methods=['POST'])
def insert_data():
    data = request.json
    col1 = data.get('col1')
    col2 = data.get('col2')

    if col1 is None or col2 is None:
        return jsonify({'message': 'col1 and col2 are required fields'}), 400

    try:
        cursor = conn.cursor()

        # Insert data into the table
        insert_query = "INSERT INTO API_TEST (col1, col2) VALUES (%s, %s)"
        cursor.execute(insert_query, (col1, col2))
        conn.commit()
        return jsonify({'message': 'Data added successfully'}), 201
    except Exception as e:
        return jsonify({'message': 'Failed to add data', 'error': str(e)}), 500
    finally:
        cursor.close()
        conn.close()


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=105, debug=True)
