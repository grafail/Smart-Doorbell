import cv2
import face_recognition
import os
import time
import pygame
from pygame import mixer
import numpy as np
from threading import Thread
import threading
import urllib.request


cam = cv2.VideoCapture(1)
flag = True
faces_list = []
nicknames = []
unknown_flag = False
timeout = 0

def send_warning_email():
    urllib.request.urlopen('http://192.168.43.195:5000/say/?text=Unknown%20face%20was%20detected!')
    smtp_server = 'localhost'
    smtp_port = 1025
    import smtplib
    from email.mime.text import MIMEText
    from email.mime.multipart import MIMEMultipart
    from email.mime.image import MIMEImage
    server = smtplib.SMTP(smtp_server, smtp_port)
    server.ehlo()
    msg = MIMEMultipart()
    msg['From'] = 'test@localhost'
    msg['To'] = 'user@localhost'
    msg['Subject'] = "Intruder Detected"
    msg.attach(MIMEText('An unknown face was detected!', 'plain'))
    with open('webcam.png', 'rb') as fp:
        img = MIMEImage(fp.read())
    msg.attach(img)
    text = msg.as_string()
    server.send_message(msg)

def unknown_found():
    global unknown_flag
    while True:
        if unknown_flag == True:
            mixer.init()
            mixer.music.load('unknown.mp3')
            mixer.music.play()
            while pygame.mixer.music.get_busy():
             pygame.time.wait(100)
             unknown_flag = False

t = threading.Thread(target=unknown_found)
t.start()

def get_nickname(str):  
    return (os.path.splitext(str)[0])

def scan_stored_faces():
    global faces_list
    global nicknames
    for root,dirs,files in os.walk('./faces'):
        for name in files:
            filepath=os.path.join(root, name)
            nicknames.append(get_nickname(name))
            img = face_recognition.load_image_file(filepath)
            try:
                faces_list.append(face_recognition.face_encodings(img)[0])
            except IndexError:
                print("No face found in picture!")
                quit()
    


def main():
    global timeout
    global unknown_flag
    global flag
    global faces_list
    global nicknames
    if flag:
        flag= not flag
    else:

        ret, img_big = cam.read()

        img = cv2.resize(img_big, (0, 0), fx=0.25, fy=0.25)[:, :, ::-1]
        
        face_locations = face_recognition.face_locations(img)
        face_encodings = face_recognition.face_encodings(img, face_locations)

        face_names = []
        for face_encoding in face_encodings:
            matches = face_recognition.compare_faces(faces_list, face_encoding)
            name = "Unknown"

            if True in matches:
                first_match_index = matches.index(True)
                name = nicknames[first_match_index]

            face_names.append(name)
            if name == 'Unknown':
                unknown_flag = True
                if (time.time() - 300) > timeout:
                    print("SENDING EMAIL!")
                    send_warning_email()
                    timeout = time.time()

        for (top, right, bottom, left), name in zip(face_locations, face_names):
            top *= 4
            right *= 4
            bottom *= 4
            left *= 4

            cv2.rectangle(img_big, (left, top), (right, bottom), (0, 0, 255), 2)

            cv2.rectangle(img_big, (left, bottom - 35), (right, bottom), (0, 0, 255), cv2.FILLED)
            font = cv2.FONT_HERSHEY_DUPLEX
            cv2.putText(img_big, name, (left + 6, bottom - 6), font, 1.0, (255, 255, 255), 1)

        cv2.imwrite('webcam.png',img_big)        
        cv2.imshow('img',img_big)
        cv2.waitKey(1)
        flag = not flag

cv2.destroyAllWindows()

scan_stored_faces()

while True:
    main()

cam.release()
