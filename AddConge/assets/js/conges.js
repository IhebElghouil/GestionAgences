$(function () {

  const $btnSave = $('#SaveData');
  const $alert = $('#success-alert');

  function resetForm() {
    $('#NbJoursConge').val(0);
    $('#NbJoursResteSolde').val($('#Solde').val());
    $btnSave.prop('disabled', true);
    $('#buttonStyle').hide();
  }

  function showError(msg) {
    $alert.text(msg).fadeIn().delay(3000).fadeOut();
    resetForm();
  }

  $('#DateDebut, #DateFin, #TypeRepos, #JourDeReposFixe').on('change', function () {

    const dateDebut = $('#DateDebut').val();
    const dateFin   = $('#DateFin').val();

    if (!dateDebut || !dateFin) return;

    $.get('ajax/verifdate.php', {
      DateDebut: dateDebut,
      DateFin: dateFin,
      mecano: window.mecano
    })
    .done(res => {
      if (res == 0) {
        showError('الراحة مسجلة سابقا');
      } else {
        $('#buttonStyle').show();
        $btnSave.prop('disabled', false);
      }
    });
  });

});
