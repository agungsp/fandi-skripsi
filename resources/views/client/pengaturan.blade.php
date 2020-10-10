@extends('layouts.sb-admin')

{{-- META --}}
@section('meta')

@endsection

{{-- CSS --}}
@section('css')

@endsection

{{-- TITLE --}}
@section('title', 'Pengaturan')

{{-- CONTENT TITLE --}}
@section('title-content', 'Pengaturan')

{{-- CONTENT --}}
@section('content')
    <div class="row mb-5">
        <div class="col-lg-5 col-md-6">
            <div class="card shadow">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col d-flex justify-content-center">
                            <label for="avatar" role="button">
                                <img src="{{ asset('img/avatar.svg') }}" alt="avatar" id="avatar_view" class="img-thumbnail rounded-circle" width="100">
                            </label>
                            <input type="file" class="d-none" name="avatar" id="avatar" accept="image/*">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col pl-5 pr-5 pt-4">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" class="form-control">
                            </div>
                            <div class="form-group text-center">
                                <span class="btn-link" role="button" data-toggle="modal" data-target="#modalChangePassword">
                                    Ubah password
                                </span>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="confidence" class="d-flex justify-content-between">
                                    Confidence
                                    <span id="confidence_value"></span>
                                </label>
                                <input type="range" min="0" max="100" value="50" step="1" name="confidence" id="confidence" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="support" class="d-flex justify-content-between">
                                    Support
                                    <span id="support_value"></span>
                                </label>
                                <input type="range" min="0" max="100" value="50" step="1" name="support" id="support" class="form-control">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary rounded-pill btn-block">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- MODAL --}}
@section('modal')
    <div id="modalChangePassword" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Ubah Password</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="old_password">Password Lama</label>
                        <div class="input-group mb-3">
                            <input type="password" id="old_password" name="old_password" class="form-control" aria-describedby="btn_old_password">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="btn_old_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="new_password">Password Baru</label>
                        <div class="input-group mb-3">
                            <input type="password" id="new_password" name="new_password" class="form-control" aria-describedby="btn_new_password">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="btn_new_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_new_password">Konfirmasi Password Baru</label>
                        <div class="input-group mb-3">
                            <input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control" aria-describedby="btn_confirm_new_password">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="btn_confirm_new_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn rounded-pill btn-sm btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="button" class="btn rounded-pill btn-sm btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- JS --}}
@section('js')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#avatar_view').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).ready(function() {
            let confidence_value = $('#confidence').val();
            let support_value = $('#support').val();
            $('#confidence_value').html(confidence_value);
            $('#support_value').html(support_value);

            $('body').on('input', '#confidence', function() {
                let value = $('#confidence').val();
                $('#confidence_value').html(value);
            });

            $('body').on('input', '#support', function() {
                let value = $('#support').val();
                $('#support_value').html(value);
            });

            $('body').on('change', '#avatar', function() {
                readURL(this);
            });
        });
    </script>
@endsection
