@csrf

<div class="card-body">
    {{-- Servidor --}}
    <div class="form-group">
        <label for="ldap_server">Servidor LDAP</label>
        <input type="text" name="ldap_server" id="ldap_server"
            class="form-control @error('ldap_server') is-invalid @enderror"
            value="{{ old('ldap_server', $ldap->ldap_server ?? '') }}" maxlength="120" required>
        @error('ldap_server')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    {{-- Usuário --}}
    <div class="form-group">
        <label for="ldap_user">Usuário LDAP</label>
        <input type="text" name="ldap_user" id="ldap_user" class="form-control @error('ldap_user') is-invalid @enderror"
            value="{{ old('ldap_user', $ldap->ldap_user ?? '') }}" maxlength="120" required>
        @error('ldap_user')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    {{-- Senha --}}
    <div class="form-group">
        <label for="ldap_pass">Senha LDAP</label>
        <input type="password" name="ldap_pass" id="ldap_pass"
            class="form-control @error('ldap_pass') is-invalid @enderror"
            value="{{ old('ldap_pass', $ldap->ldap_pass ?? '') }}" maxlength="120" required>
        @error('ldap_pass')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    {{-- Base (Tree) --}}
    <div class="form-group">
        <label for="ldap_tree">Base (Tree) LDAP</label>
        <input type="text" name="ldap_tree" id="ldap_tree" class="form-control @error('ldap_tree') is-invalid @enderror"
            value="{{ old('ldap_tree', $ldap->ldap_tree ?? '') }}" maxlength="120" required>
        @error('ldap_tree')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    {{-- Filtro --}}
    <div class="form-group">
        <label for="ldap_filter">Filtro LDAP</label>
        <input type="text" name="ldap_filter" id="ldap_filter"
            class="form-control @error('ldap_filter') is-invalid @enderror"
            value="{{ old('ldap_filter', $ldap->ldap_filter ?? '') }}" maxlength="200" required>
        @error('ldap_filter')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>