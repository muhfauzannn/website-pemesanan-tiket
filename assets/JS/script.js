$(document).ready(function(){
    $('#kd_kelas').change(function(){
        var kelasId = $(this).val();
        var kdKereta = 'K001'; // Replace with actual kd_kereta if dynamic
        var gerbongSelect = $('#kd_gerbong');
        var bangkuSelect = $('#kd_bangku');

        // Reset gerbong and bangku
        gerbongSelect.html('<option value="">Pilih Gerbong</option>');
        bangkuSelect.html('<option value="">Pilih Bangku</option>');

        if (kelasId) {
            // Fetch gerbong based on selected kelas and kereta
            $.ajax({
                url: 'function/get_gerbong.php',
                type: 'POST',
                data: {
                    kelas: kelasId,
                    kereta: kdKereta
                },
                dataType: 'json',
                success: function(data) {
                    $.each(data, function(key, value) {
                        gerbongSelect.append('<option value="'+ value.kd +'">'+ value.kd +'</option>');
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("Error: " + textStatus + " " + errorThrown);
                }
            });

            // Fetch bangku based on selected kelas
            $.ajax({
                url: 'function/get_bangku.php',
                type: 'POST',
                data: {kelas: kelasId},
                dataType: 'json',
                success: function(data) {
                    $.each(data, function(key, value) {
                        bangkuSelect.append('<option value="'+ value.kd +'">'+ value.no +'</option>');
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("Error: " + textStatus + " " + errorThrown);
                }
            });
        }
    });

    $('#kd_gerbong').change(function(){
        var gerbongId = $(this).val();
        var bangkuSelect = $('#kd_bangku');

        // Reset bangku
        bangkuSelect.html('<option value="">Pilih Bangku</option>');

        if (gerbongId) {
            // Fetch bangku based on selected gerbong
            $.ajax({
                url: 'function/get_bangku.php',
                type: 'POST',
                data: {gerbong: gerbongId},
                dataType: 'json',
                success: function(data) {
                    $.each(data, function(key, value) {
                        bangkuSelect.append('<option value="'+ value.kd +'">'+ value.no +'</option>');
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("Error: " + textStatus + " " + errorThrown);
                }
            });
        }
    });
});
