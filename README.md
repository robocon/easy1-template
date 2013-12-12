วิธีการติดตั้ง
--------------------------------------
- ดาวโหลดไฟล์แบบ zip
- แตกไฟล์ไว้ในโฟเดอร์ templates ชื่อ easy1
- login เข้า admin ไปที่ config แล้วเลือก template ที่ชื่อ easy1 ได้เลย

วิธีการ Override Modules ตัวเดิม
--------------------------------------
ทำได้โดยการเพิ่มไฟล์เข้าไปใน templates/easy1/modules/
ตัวอย่างเช่น 
ต้องการเขียน Block "เมนูหลัก" ให้เป็นรูปแบบใหม่ก็ให้เพิ่มไฟล์ที่ชื่อ mainmenu.php เข้าไปใน templates/easy1/modules/block/mainmenu.php 
เป็นต้น