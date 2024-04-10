<?php

	$connect = mysqli_connect("", "", "", "") or die("err mc");
	mysqli_set_charset($connect, "utf8mb4");

	if($_GET["showAll"] >= 1){
		
		$records = mysqli_query($connect, "
			SELECT 
				KJ.`id` AS 'id', 
				KS.`full_name` AS 'student_full_name', 
				KD.`name` AS 'discipline_name', 
				KJ.`result` AS 'result', 
				KJ.`date` AS 'date', 
				KSG.`name` AS 'study_group_name', 
				KT.`name` AS 'teacher_name' 
			FROM `korytov_journal` KJ 
				JOIN `korytov_students` KS ON KJ.`id_student` = KS.`id`
				JOIN `korytov_disciplines` KD ON KJ.`id_discipline` = KD.`id`
				JOIN `korytov_study_groups` KSG ON KJ.`id_study_group` = KSG.`id`
				JOIN `korytov_teachers` KT ON KJ.`id_teacher` = KT.`id`
			ORDER BY KJ.`id` DESC
			LIMIT ".($_GET["showAll"]*20).",20
		");
		
		while($record = mysqli_fetch_assoc($records)){
			
			echo '
				<tr>
					<td>'.$record['id'].'</td>
					<td>'.$record['student_full_name'].'</td>
					<td>'.$record['discipline_name'].'</td>
					<td>'.$record['result'].'</td>
					<td>'.$record['date'].'</td>
					<td>'.$record['study_group_name'].'</td>
					<td>'.$record['teacher_name'].'</td>
				</tr>
			';
			
		}
		
		exit;
		
	}else if(strlen($_GET['search']) >= 1){
		
		$records = mysqli_query($connect, "
		SELECT * FROM (SELECT 
			KJ.`id` AS 'id', 
			KS.`full_name` AS 'student_full_name', 
			KD.`name` AS 'discipline_name', 
			KJ.`result` AS 'result', 
			KJ.`date` AS 'date', 
			KSG.`name` AS 'study_group_name', 
			KT.`name` AS 'teacher_name' 
		FROM `korytov_journal` KJ 
			JOIN `korytov_students` KS ON KJ.`id_student` = KS.`id`
			JOIN `korytov_disciplines` KD ON KJ.`id_discipline` = KD.`id`
			JOIN `korytov_study_groups` KSG ON KJ.`id_study_group` = KSG.`id`
			JOIN `korytov_teachers` KT ON KJ.`id_teacher` = KT.`id`
		) T WHERE T.`student_full_name` LIKE '%".$_GET['search']."%' OR T.`discipline_name` LIKE '%".$_GET['search']."%'
		");
		
		if(mysqli_num_rows($records) >= 1){
		
			echo '
				<table border="1">
					<caption>Журнал учёта успеваемости</caption>
					<thead>
					  <tr>
						<th>ИД</th>
						<th>Студент</th>
						<th>Дисциплина</th>
						<th>Оценка</th>
						<th>Дата</th>
						<th>Группа</th>
						<th>Преподаватель</th>
					  </tr>
					</thead>
					<tbody>
			';
			
			while($record = mysqli_fetch_assoc($records)){
				
				echo '
					<tr>
						<td>'.$record['id'].'</td>
						<td>'.$record['student_full_name'].'</td>
						<td>'.$record['discipline_name'].'</td>
						<td>'.$record['result'].'</td>
						<td>'.$record['date'].'</td>
						<td>'.$record['study_group_name'].'</td>
						<td>'.$record['teacher_name'].'</td>
					</tr>
				';
				
			}
			
			echo '
				</tbody>
				  </table>
			';
		
		}else{
			
			echo '
				<table border="1">
					<caption>Нет записей с данным поисковым запросом</caption>
				</table>
			';
			
		}
		
		exit;
		
	}

?>
<!DOCTYPE html>
<html>
    <head>
		<title>Курсовой проект - Журнал успеваемости</title>
		<meta name="viewport" content="display=display-width, initial-scale=1">
		<link rel="icon" href="https://cdn-icons-png.flaticon.com/512/3235/3235042.png">
        <style>
            *{
                margin: 0px;
            }

            body{
                background: #040404;
            }
            body::-webkit-scrollbar {
                width: 20px;
            }
            body::-webkit-scrollbar-track {
                background-color: darkred;
                border-radius: 10px 0px 0px 10px;
                margin: 10px 0px;
            }
            body::-webkit-scrollbar-thumb {
                box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
                background: sienna;
                border-radius: 10px 0px 0px 10px;
            }

            header{
                position: relative;
                width: 720px;
                left: calc(50vw - 360px);
                display: flex;
                flex-direction: row;
                align-items: center;
                box-sizing: border-box;
                padding: 10px;
                margin: 10px 0px;
                z-index: 1;
            }
            header div{
                position: relative;
                width: 50%;
                display: flex;
                align-items: center;
            }

            header div.left img{
                position: relative;
                width: 40px;
                height: 40px;
            }
            header div.left span{
                position: relative;
                font-size: 120%;
                font-weight: bold;
                padding: 0px 10px;
                color: white;
            }

            header div.right{
                justify-content: flex-end;
            }
            header div.right img{
                position: relative;
                width: 13px;
                left: 23px;
                z-index: 1;
            }
            header div.right input{
                position: relative;
                padding: 5px 5px 5px 30px;
                border-radius: 5px;
                border: none;
                background: #e6e6e6;
                font-size: 90%;
				box-shadow: 7px 7px 7px rgba(0, 0, 0, 0.5)
            }

            main{
                position: relative;
                width: 720px;
                /* height: 1000px; */
                background: white;
                z-index: 1;
                border-radius: 20px;
                left: calc(50vw - 360px);
                /* opacity: 0.6; */
                padding: 10px;
                box-sizing: border-box;
				margin: 0px 0px 30px 0px;
				box-shadow: 7px 7px 7px rgba(0, 0, 0, 0.5)
            }
            main table{
                position: relative;
                width: 100%;
                text-align: center;
                border-radius: 0px 0px 10px 10px;
            }
            main table caption{
                position: relative;
                font-size: 110%;
                font-weight: bold;
                padding: 0px 0px 10px 0px;
            }
            main table tr#avg{
                text-align: left;
            }
            main table tr#avg:last-child td:first-child{
                border-radius: 0px 0px 0px 10px;
            }
            main table tr#avg:last-child td:last-child{
                border-radius: 0px 0px 10px 0px;
            }
			
			main div.showAll{
			    position: relative;
				display: block;
				padding: 10px;
				background: steelblue;
				margin: 10px 0px 0px 0px;
				border-radius: 5px;
				color: white;
				font-weight: bold;
				text-align: center;
				cursor: pointer;
				user-select: none;
			}

            img#background{
                position: absolute;
                width: 100%;
                top: 0px;
                left: 0px;
            }
        </style>
		<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    </head>
    <body>
        <header>
            <div class="left">
                <img src="https://cdn-icons-png.flaticon.com/512/2944/2944349.png">
                <span>EJournal</span>
            </div>
            <div class="right">
                <img src="https://uxwing.com/wp-content/themes/uxwing/download/user-interface/search-icon.png">
                <input type="search" placeholder="Search...">
            </div>
        </header>

        <main>
            <table border="1">
                <caption>Журнал учёта успеваемости</caption>
                <thead>
                  <tr>
                    <th>ИД</th>
                    <th>Студент</th>
                    <th>Дисциплина</th>
                    <th>Оценка</th>
                    <th>Дата</th>
                    <th>Группа</th>
                    <th>Преподаватель</th>
                  </tr>
                </thead>
                <tbody>
				<?php
				
					$records = mysqli_query($connect, "
						SELECT 
							KJ.`id` AS 'id', 
							KS.`full_name` AS 'student_full_name', 
							KD.`name` AS 'discipline_name', 
							KJ.`result` AS 'result', 
							KJ.`date` AS 'date', 
							KSG.`name` AS 'study_group_name', 
							KT.`name` AS 'teacher_name' 
						FROM `korytov_journal` KJ 
							JOIN `korytov_students` KS ON KJ.`id_student` = KS.`id`
							JOIN `korytov_disciplines` KD ON KJ.`id_discipline` = KD.`id`
							JOIN `korytov_study_groups` KSG ON KJ.`id_study_group` = KSG.`id`
							JOIN `korytov_teachers` KT ON KJ.`id_teacher` = KT.`id`
						ORDER BY KJ.`id` DESC
						LIMIT 20
					");
					
					while($record = mysqli_fetch_assoc($records)){
						
						echo '
							<tr>
								<td>'.$record['id'].'</td>
								<td>'.$record['student_full_name'].'</td>
								<td>'.$record['discipline_name'].'</td>
								<td>'.$record['result'].'</td>
								<td>'.$record['date'].'</td>
								<td>'.$record['study_group_name'].'</td>
								<td>'.$record['teacher_name'].'</td>
							</tr>
						';
						
					}
				
				?>
                </tbody>
              </table>
			  
			  <div class="showAll">Показать ещё</div>
			  
        </main>

        <img src="https://img.goodfon.ru/original/1280x960/c/fb/cat-yellow-eyes-paw-funny-cute-simple-background-digital-art.jpg" id="background">
		
		<script>
		
			window.onload = function(){
				
				let showAll = 1;
				let main = $("main").html();;
				
				$("body").on("click", "main div.showAll", function(){
					
					$("main div.showAll").css({"display": "none"});
					
					$.ajax({
						url: "",
						method: "GET",
						data: {
							showAll: showAll,
						},
						success: function(data){
							
							showAll++;
							$("main table").append(data);
							
							if(showAll <= ($("main table tr").length/20)){
								$("main div.showAll").css({"display": "block"});
							}
							
							main = $("main").html();;
							
						}
					});
					
				});
				
				$("body").on("input", "header div.right input", function(){
					
					if($(this).val().length <= 0){
						$("main").html(main);
					}else{
						$.ajax({
							url: "",
							method: "GET",
							data: {
								search: $(this).val(),
							},
							success: function(data){
								
								$("main").html(data);
								
							}
						});
					}
					
				});
				
			};
		
		</script>
		
    </body>
</html>