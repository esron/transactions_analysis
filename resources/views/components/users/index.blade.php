<x-layout>
    <div class="container mt-4">
        <h1 class="text-center">USUÁRIOS CADASTRADOS</h1>
        @if($users->count() === 0)
            <h2 class="mt-5 text-center">Nenhum usuário foi encontrado</h2>
        @else
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
                            data-bs-remove-user-route="{{ route('users.destroy', [$user]) }}"
                        >
                            Remover
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
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
                        <form id="removeUserForm" action="" method="post">
                            @method('DELETE')
                            @csrf
                            <input id="userId" name="id" hidden value="">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger" id="removeUserButton">Remover</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const removeUserModal = document.getElementById('removeUserModal')
        removeUserModal.addEventListener('show.bs.modal', (event) => {
            // Button that triggered the modal
            const button = event.relatedTarget

            // Extract info from data-bs-* attributes
            const userName = button.getAttribute('data-bs-user-name')
            const userId = button.getAttribute('data-bs-user-id')
            const removeUserRoute = button.getAttribute('data-bs-remove-user-route')

            // Update the modal's content.
            const modalBody = removeUserModal.querySelector('.modal-body')
            const removeUserForm = document.getElementById('removeUserForm')
            removeUserForm.setAttribute('action', removeUserRoute)
            modalBody.textContent = `Deseja mesmo remover o usuário ${userName}`
        })
    </script>
</x-layout>
