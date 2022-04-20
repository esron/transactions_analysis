<x-layout>
    <div class="container mt-4">
        <h1 class="text-center">USUÁRIOS CADASTRADOS</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">NOME</th>
                    <th scope="col">EMAIL</th>
                    <th scope="col">AÇÕES</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <th scope="row">{{ $user->id }}</th>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <a href="{{ route('users.edit', [$user]) }}" class="btn btn-primary">Editar</a>
                        <button
                            type="button"
                            class="btn btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#removeUserModal"
                            data-bs-user-name="{{ $user->name }}"
                            data-bs-user-id="{{ $user->id }}"
                        >
                            Remover
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Modal -->
        <div class="modal fade" id="removeUserModal" tabindex="-1" role="dialog" aria-labelledby="removeUserModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="removeUserModalTitle">Remover usuário</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="removeUserButton">Remover</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var removeUserModal = document.getElementById('removeUserModal')
        removeUserModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            var button = event.relatedTarget
            // Extract info from data-bs-* attributes
            var userName = button.getAttribute('data-bs-user-name')
            var userId = button.getAttribute('data-bs-user-id')

            // Update the modal's content.
            var modalBody = removeUserModal.querySelector('.modal-body')
            modalBody.textContent = `Deseja mesmo remover o usuário ${userName}`
        })
    </script>
</x-layout>
