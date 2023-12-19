<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios</title>
    <!-- Agrega el CDN de Vue.js -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
</head>
<body>
    <div id="app">
        <h1>Lista de Usuarios</h1>
        <button @click="obtenerUsuarios">Listar Usuarios</button>
        <button @click="mostrarFormulario">Agregar Usuario</button>

        <div v-if="mostrarAgregar">
            <h2>Agregar Nuevo Usuario</h2>
            <form @submit.prevent="agregarNuevoUsuario">
                <label for="id_usuario">Nu. Identificacion:</label>
                <input type="number" id="id_usuario" v-model="nuevoUsuario.id_usuario" required><br><br>    

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" v-model="nuevoUsuario.nombre" required><br><br>
        
                <label for="email">Email:</label>
                <input type="email" id="email" v-model="nuevoUsuario.email" required><br><br>
        
                <label for="telefono">Telefono:</label>
                <input type="number" id="telefono" v-model.number="nuevoUsuario.telefono" required><br><br>
        
                <button type="submit">Agregar</button>
            </form>
        </div>

        <button @click="eliminarUsuario">Eliminar Usuario</button>

        <div v-if="mostrarEliminar">
            <h2>Eliminar Usuario</h2>
            <form @submit.prevent="eliminarUsuario">
                <label for="id_eliminarUsuario">Ingrese la identificacion del usuario a eliminar:</label>
                <input type="number" id="id_eliminarUsuario" v-model="elimiUsuario.id_elusuario" required><br><br>
                <button type="submit">Eliminar</button>
            </form>
        </div>

        <button @click="actualizarUsuario">Actualizar Usuario</button>

        <table v-if="usuarios.length > 0" border="1">
            <thead>
                <tr>
                    <th>Identificacion</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Telefono</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="usuario in usuarios" :key="usuario.id_usuario">
                    <td>{{ usuario.id_usuario }}</td>
                    <td>{{ usuario.nombre }}</td>
                    <td>{{ usuario.email }}</td>
                    <td>{{ usuario.telefono }}</td>
                </tr>
            </tbody>
        </table>
        <p v-else>No hay usuarios cargados</p>
    </div>

    <script>
        new Vue({
            el: '#app',
            data: {
                usuarios: [],
                mostrarAgregar: false,
                nuevoUsuario: {
                    id_usuario:'',
                    nombre: '',
                    email: '',
                    telefono: ''
                },
                id_elusuario
            },
            methods: {
                obtenerUsuarios() {
                    fetch('Baken.php') // Ruta del php
                        .then(response => response.json())
                        .then(data => {
                            this.usuarios = data;
                        })
                        .catch(error => console.error('Error:', error));
                },
                mostrarFormularioAgregar() {
                    this.mostrarAgregar = !this.mostrarAgregar;
                },
                agregarNuevoUsuario() {
                    fetch('Baken.php', {
                        method: 'POST',
                        body: JSON.stringify({
                            accion: 'agregar',
                            id_usuario: this.nuevoUsuario.id_usuario,
                            nombre: this.nuevoUsuario.nombre,
                            email: this.nuevoUsuario.email,
                            telefono: this.nuevoUsuario.telefono
                        }),
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.text())
                    .then(data => {
                        console.log(data);
                        this.obtenerUsuarios();

                        this.nuevoUsuario.id_usuario = '';
                        this.nuevoUsuario.nombre = '';
                        this.nuevoUsuario.email = '';
                        this.nuevoUsuario.telefono = '';

                        this.mostrarAgregar = false;
                    })
                    .catch(error => console.error('Error:', error));
                },
                mostrarFormularioEliminar() {
                    this.mostrarEliminar = !this.mostrarEliminar;
                }
                eliminarUsuario() {
                    // Lógica para eliminar usuario, similar al método agregarNuevoUsuario
                },
                actualizarUsuario() {
                    // Lógica para actualizar usuario, similar al método agregarNuevoUsuario
                }
            }
        });
    </script>
</body>
</html>