<?php

// require("connection.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="style.css" />
</head>

<body>

	<div class="wrapper">
		<form class="row" id="add-movie">
			<div class="col-4">
				<label>Title <input type="text" name="movie" placeholder="Movie Title" required /></label>
				<label>Rating <input type="number" name="rating" placeholder="Rate the movie from 0 to 10" min="0" max="10" required /></label>
				<input type="submit" value="Add Movie" />
			</div>
		</form>
	</div>

	<div class="wrapper">
		<div class="row">
			<table class="movie-list">
				<tr>
					<th class="sortable">
						<span class="text">Movie</span>
						<span class="arrow-btn">
							<button class="asc movie"><i class="fa-solid fa-chevron-up"></i></button>
							<button class="desc movie"><i class="fa-solid fa-chevron-down"></i></button>
						</span>
					</th>
					<th class="sortable">
						<span class="text">Rating</span>
						<span class="arrow-btn">
							<button class="asc rating"><i class="fa-solid fa-chevron-up"></i></button>
							<button class="desc rating"><i class="fa-solid fa-chevron-down"></i></button>
						</span>
					</th>
					<th>Delete</th>
				</tr>
			</table>
		</div>
	</div>


	<script>
		function renderRow(row) {

			const html = `				
					<td>${row.movie}</td>
					<td>${row.rating}</td>
					<td><a href="#" class="btn">Delete</a></td>				
			`;

			const tr = document.createElement("tr");
			tr.innerHTML = html;

			const table = document.querySelector(".movie-list");
			if (table.rows.length === 1) {
				table.style.display = "table";
			}
			table.append(tr);
		}

		function convertTableToJSON() {

			// debugger;

			const rows = [];
			const keys = [];

			const table = document.querySelector(".movie-list");

			Array.from(table.rows).forEach((row, idx) => {
				// debugger;
				console.log(row.querySelectorAll("th"));

				const obj = {};

				if (idx === 0) {
					Array.from(row.querySelectorAll("th")).forEach((th, idx) => {
						console.log(th);
						keys.push(th.textContent.trim());
					});
				} else {
					Array.from(row.querySelectorAll("td")).forEach((td, idx) => {
						console.log(td);
						if (idx == 1) {
							obj[keys[idx].toLowerCase()] = parseInt(td.textContent.trim(), 10);
						} else {
							obj[keys[idx].toLowerCase()] = td.textContent.trim();
						}
					});

					rows.push(obj);
				}

			});

			console.log(rows);
			return rows;
		}

		function renderTable(rows) {

			const table = document.querySelector(".movie-list")

			// remove all the rows excpt the first
			table.querySelectorAll("tr:not(:first-child)").forEach(tr => tr.remove());

			rows.forEach((row) => {
				renderRow(row);
			})

		}

		let form = document.querySelector("#add-movie");

		form.addEventListener("submit", function(event) {
			event.preventDefault();

			// debugger;

			let formData = new FormData(form);
			let jsonBody = Object.fromEntries(formData.entries());
			console.log(jsonBody);

			fetch("/process.php", {
					method: "POST",
					headers: {},
					body: JSON.stringify({
						...jsonBody,
						type: "add"
					})
				})
				.then(response => response.json())
				.then((data) => {
					console.log(data);
					alert("Row Inserted Successfully");
					form.reset();
					renderRow(jsonBody);
					form[0].focus();
				})

		})

		document.addEventListener("click", function(event) {
			if (event.target.className === "btn") {
				if (confirm("Are you sure?")) {
					event.preventDefault();
					console.log("clicked");
					// debugger;
					const table = document.querySelector(".movie-list");
					if (table.rows.length === 2) {
						table.style.display = "none";
					}
					table.removeChild(event.target.closest("tr"))
				}
			}

			// debugger;
			// sorting btns clicked
			if (event.target.className.includes("fa")) {
				const [order, column] = event.target.closest("button").className.split(" ");
				console.log(order, column);
				const rows = convertTableToJSON();

				const sortNumeric = (column, order) => {
					if (order == "asc") {
						return (a, b) => a[column] - b[column];
					} else {
						return (a, b) => b[column] - a[column];
					}
				};

				const sortText = (column, order) => {

					if (order === "asc") {

						return (a, b) => {

							const nameA = a[column].toUpperCase();
							const nameB = b[column].toUpperCase();

							if (nameA < nameB) {
								return -1;
							}
							if (nameA > nameB) {
								return 1;
							}

							// names must be equal
							return 0;
						}

					} else {
						return (a, b) => {

							const nameA = a[column].toUpperCase();
							const nameB = b[column].toUpperCase();

							if (nameA < nameB) {
								return 1;
							}
							if (nameA > nameB) {
								return -1;
							}

							// names must be equal
							return 0;
						}
					}


					console.log(rows);
				}

				if (column == "rating") {
					// rows.sort(sortNumeric("rating", "desc"))
					rows.sort(sortNumeric(column, order))
				} else {
					rows.sort(sortText(column, order))
				}

				console.log(rows);

				renderTable(rows);

			}
		});
	</script>

</body>

</html>