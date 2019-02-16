import cv2
import tkinter as tk
import tkinter.messagebox
import tkinter.simpledialog
import face_recognition
import matplotlib.pyplot as plt

root = tk.Tk()
root.withdraw()


def scan():
    faces = []
    cam = cv2.VideoCapture(1)
    while faces == []:
        ret, img_big = cam.read()
        img = cv2.resize(img_big, (0, 0), fx=0.25, fy=0.25)
        faces = face_recognition.face_locations(img)
        for top, right, bottom, left in faces:
            cv2.rectangle(img_big,(left*4, top*4), (right*4, bottom*4), color=(0, 0, 255))
        cv2.imshow('img',img_big)
        cv2.waitKey(1)
    cv2.destroyAllWindows()
    cam.release()
    confirm(img_big,img)
def confirm(img_big,img):
    cv2.imshow('Scanned Image',img_big)
    cv2.waitKey(5000)
    cv2.destroyAllWindows()
    blurry = tk.messagebox.askyesno("Face Scan","Is the image blurry?")
    if blurry:
        scan()
    else:
        nickname = tk.simpledialog.askstring("Nickname", "Give nickname for face")
        cv2.imwrite('./faces/'+nickname+'.png',img)


scan()