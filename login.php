<!DOCTYPE html>
<html>

<head>
    <title>Login</title>

    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-auth-compat.js"></script>
</head>

<body>

    <input type="email" id="email" placeholder="Correo">
    <input type="password" id="password" placeholder="Contraseña">
    <button onclick="login()">Entrar</button>

    <script>
        const firebaseConfig = {
            apiKey: "AIzaSyAQ9mz40-PMbjqFIPqG0gPiXT-Lmc6pJO4",
            authDomain: "registrocompactacioneroca.firebaseapp.com",
            databaseURL: "https://registrocompactacioneroca-default-rtdb.firebaseio.com",
            projectId: "registrocompactacioneroca",
            storageBucket: "registrocompactacioneroca.appspot.com",
            messagingSenderId: "519042066380",
            appId: "1:519042066380:web:e8d21cf61fc13b563f5f69",
            measurementId: "G-QVWV7C3GCP"
        };

        firebase.initializeApp(firebaseConfig);
    </script>
    <script>
        function login() {
            firebase.auth().signInWithEmailAndPassword(
                    email.value,
                    password.value
                )
                .then(user => user.user.getIdToken())
                .then(token => {
                    return fetch('validar_firebase.php', {
                        method: 'POST',
                        credentials: 'include',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            token
                        })
                    });
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'ok') {
                        window.location.href = 'index.php';
                    } else {
                        throw new Error('No se pudo crear sesión');
                    }
                })
                .catch(err => alert(err.message));
        }
    </script>



</body>

</html>