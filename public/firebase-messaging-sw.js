// Import các thư viện Firebase cần thiết cho service worker
importScripts('https://www.gstatic.com/firebasejs/11.8.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/11.8.1/firebase-messaging-compat.js');

// Cấu hình Firebase, giống với file chính
firebase.initializeApp({
  apiKey: "AIzaSyC4J11K-9JQZbCDx8vpf611g_MDUNqQKw",
  authDomain: "daibiquan-cc28d.firebaseapp.com",
  projectId: "daibiquan-cc28d",
  storageBucket: "daibiquan-cc28d.firebasestorage.app",
  messagingSenderId: "429624254025",
  appId: "1:429624254025:web:4e800a484c2da8809b6998",
  measurementId: "G-65DPL7QV2F"
});

const messaging = firebase.messaging();

// Optional: xử lý notification khi web app không active
messaging.onBackgroundMessage(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
    // icon: '/your-icon.png'
  };

  self.registration.showNotification(notificationTitle,
    notificationOptions);
});


