# : python.py
import sys

# todo: read data from stdin
data = sys.stdin.read()



if len(sys.argv) > 1:
    # sys.argv[0] هو اسم السكريبت
    # sys.argv[1] هو أول معطى تم تمريره
    input_data = sys.argv[1]
    print(f"Received input: {input_data}")
else:    
    # todo : write data to stdout
    print(f"Received: {data.strip()}")
    print("Laravel command")