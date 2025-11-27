  function handleStampChange() {
    const stampSelect = document.querySelector('select[name="stamp"]');
    const statusSelect = document.querySelector('select[name="status"]');
    const dosenSelect = document.querySelector('select[name="stamped_dosen"]');
    const stampValue = stampSelect.value;

    // Disable invalid statuses if Not yet
    [...statusSelect.options].forEach(opt => {
      opt.disabled = (stampValue === 'Not yet' && !['Pending', 'Revisi'].includes(opt.value));
    });

    // Disable dosen dropdown if Not yet
    dosenSelect.disabled = (stampValue === 'Not yet');
  }
  document.addEventListener('DOMContentLoaded', handleStampChange);