-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 07 2026 г., 20:01
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `svgtk_portal`
--

-- --------------------------------------------------------

--
-- Структура таблицы `attendance`
--

CREATE TABLE `attendance` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `subject_id` int NOT NULL,
  `date` date NOT NULL,
  `status` enum('present','absent','late','excused') COLLATE utf8mb4_unicode_ci DEFAULT 'present'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `subject_id`, `date`, `status`) VALUES
(1, 1, 2, '2025-09-05', 'present'),
(2, 1, 2, '2025-09-12', 'present'),
(3, 1, 2, '2025-09-19', 'absent'),
(4, 2, 2, '2025-09-05', 'present'),
(5, 2, 2, '2025-09-12', 'late'),
(6, 2, 2, '2025-09-19', 'present'),
(7, 3, 2, '2025-09-05', 'absent'),
(8, 3, 2, '2025-09-12', 'present'),
(9, 3, 2, '2025-09-19', 'present'),
(10, 4, 6, '2025-09-06', 'present'),
(11, 4, 6, '2025-09-13', 'present'),
(12, 4, 6, '2025-09-20', 'present'),
(13, 5, 6, '2025-09-06', 'present'),
(14, 5, 6, '2025-09-13', 'absent'),
(15, 5, 6, '2025-09-20', 'excused'),
(16, 6, 7, '2025-09-04', 'present'),
(17, 6, 7, '2025-09-11', 'present'),
(18, 6, 7, '2025-09-18', 'present'),
(19, 7, 7, '2025-09-04', 'late'),
(20, 7, 7, '2025-09-11', 'present'),
(21, 7, 7, '2025-09-18', 'absent'),
(22, 8, 8, '2025-09-03', 'present'),
(23, 8, 8, '2025-09-10', 'present'),
(24, 8, 8, '2025-09-17', 'present'),
(25, 9, 8, '2025-09-03', 'absent'),
(26, 9, 8, '2025-09-10', 'present'),
(27, 9, 8, '2025-09-17', 'present'),
(28, 10, 6, '2025-09-05', 'present'),
(29, 10, 6, '2025-09-12', 'present'),
(30, 10, 6, '2025-09-19', 'present'),
(31, 11, 6, '2025-09-05', 'present'),
(32, 11, 6, '2025-09-12', 'late'),
(33, 11, 6, '2025-09-19', 'present'),
(34, 12, 8, '2025-09-04', 'absent'),
(35, 12, 8, '2025-09-11', 'present'),
(36, 12, 8, '2025-09-18', 'present'),
(37, 13, 9, '2025-09-03', 'present'),
(38, 13, 9, '2025-09-10', 'present'),
(39, 14, 9, '2025-09-03', 'present'),
(40, 14, 9, '2025-09-10', 'excused'),
(41, 15, 10, '2025-09-05', 'present'),
(42, 15, 10, '2025-09-12', 'absent');

-- --------------------------------------------------------

--
-- Структура таблицы `grades`
--

CREATE TABLE `grades` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `subject_id` int NOT NULL,
  `grade` tinyint NOT NULL,
  `grade_type` enum('current','final') COLLATE utf8mb4_unicode_ci DEFAULT 'final',
  `semester` tinyint DEFAULT '1',
  `academic_year` varchar(9) COLLATE utf8mb4_unicode_ci NOT NULL
) ;

--
-- Дамп данных таблицы `grades`
--

INSERT INTO `grades` (`id`, `student_id`, `subject_id`, `grade`, `grade_type`, `semester`, `academic_year`) VALUES
(1, 1, 1, 5, 'final', 1, '2024-2025'),
(2, 1, 2, 5, 'final', 1, '2024-2025'),
(3, 1, 6, 4, 'final', 1, '2024-2025'),
(4, 2, 1, 4, 'final', 1, '2024-2025'),
(5, 2, 2, 5, 'final', 1, '2024-2025'),
(6, 2, 6, 5, 'final', 1, '2024-2025'),
(7, 3, 1, 3, 'final', 1, '2024-2025'),
(8, 3, 2, 4, 'final', 1, '2024-2025'),
(9, 3, 6, 3, 'final', 1, '2024-2025'),
(10, 4, 1, 5, 'final', 1, '2024-2025'),
(11, 4, 2, 4, 'final', 1, '2024-2025'),
(12, 4, 6, 5, 'final', 1, '2024-2025'),
(13, 5, 1, 4, 'final', 1, '2024-2025'),
(14, 5, 2, 3, 'final', 1, '2024-2025'),
(15, 5, 6, 4, 'final', 1, '2024-2025'),
(16, 6, 1, 5, 'final', 1, '2024-2025'),
(17, 6, 7, 5, 'final', 1, '2024-2025'),
(18, 6, 8, 4, 'final', 1, '2024-2025'),
(19, 7, 1, 4, 'final', 1, '2024-2025'),
(20, 7, 7, 3, 'final', 1, '2024-2025'),
(21, 7, 8, 4, 'final', 1, '2024-2025'),
(22, 8, 1, 3, 'final', 1, '2024-2025'),
(23, 8, 7, 4, 'final', 1, '2024-2025'),
(24, 8, 8, 3, 'final', 1, '2024-2025'),
(25, 9, 1, 4, 'final', 1, '2024-2025'),
(26, 9, 7, 5, 'final', 1, '2024-2025'),
(27, 9, 8, 5, 'final', 1, '2024-2025'),
(28, 10, 2, 5, 'final', 1, '2024-2025'),
(29, 10, 6, 5, 'final', 1, '2024-2025'),
(30, 10, 8, 5, 'final', 1, '2024-2025'),
(31, 11, 2, 4, 'final', 1, '2024-2025'),
(32, 11, 6, 4, 'final', 1, '2024-2025'),
(33, 11, 8, 3, 'final', 1, '2024-2025'),
(34, 12, 2, 3, 'final', 1, '2024-2025'),
(35, 12, 6, 3, 'final', 1, '2024-2025'),
(36, 12, 8, 4, 'final', 1, '2024-2025'),
(37, 13, 9, 4, 'final', 1, '2024-2025'),
(38, 13, 10, 5, 'final', 1, '2024-2025'),
(39, 14, 9, 5, 'final', 1, '2024-2025'),
(40, 14, 10, 4, 'final', 1, '2024-2025'),
(41, 15, 9, 3, 'final', 1, '2024-2025'),
(42, 15, 10, 3, 'final', 1, '2024-2025');

-- --------------------------------------------------------

--
-- Структура таблицы `graduates`
--

CREATE TABLE `graduates` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `graduation_year` int NOT NULL,
  `diploma_grade` enum('red','blue') COLLATE utf8mb4_unicode_ci DEFAULT 'blue',
  `employment_status` enum('employed','unemployed','studying','unknown') COLLATE utf8mb4_unicode_ci DEFAULT 'unknown',
  `employer` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `graduates`
--

INSERT INTO `graduates` (`id`, `student_id`, `graduation_year`, `diploma_grade`, `employment_status`, `employer`) VALUES
(1, 6, 2024, 'red', 'employed', 'ТОО «Qarmet»'),
(2, 7, 2024, 'blue', 'studying', 'КарТУ им. Абылкас Сагинова'),
(3, 8, 2024, 'blue', 'employed', 'АО «Казахтелеком»'),
(4, 9, 2024, 'red', 'employed', 'ТОО «Digital Karaganda»'),
(5, 13, 2024, 'blue', 'studying', 'ЕНУ им. Л.Н. Гумилёва'),
(6, 14, 2024, 'red', 'employed', 'Министерство финансов РК');

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialty` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year_start` int NOT NULL,
  `curator` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`id`, `name`, `specialty`, `year_start`, `curator`) VALUES
(1, 'ИС-23', 'Информационные системы', 2023, 'Ахметова А.К.'),
(2, 'ИС-22', 'Информационные системы', 2022, 'Сейткали Б.М.'),
(3, 'ПМ-23', 'Программное обеспечение', 2023, 'Жумабеков Д.Т.'),
(4, 'ЭК-22', 'Экономика и бухгалтерский учёт', 2022, 'Нурланова Г.С.'),
(5, 'МН-23', 'Менеджмент', 2023, 'Касымов Р.О.');

-- --------------------------------------------------------

--
-- Структура таблицы `students`
--

CREATE TABLE `students` (
  `id` int NOT NULL,
  `full_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_id` int NOT NULL,
  `admission_year` int NOT NULL,
  `status` enum('active','expelled','graduated') COLLATE utf8mb4_unicode_ci DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `students`
--

INSERT INTO `students` (`id`, `full_name`, `group_id`, `admission_year`, `status`) VALUES
(1, 'Аманов Асылбек Ерланович', 1, 2023, 'active'),
(2, 'Бекова Динара Маратовна', 1, 2023, 'active'),
(3, 'Жумаев Нурлан Сейткалиевич', 1, 2023, 'active'),
(4, 'Касымова Айгерим Даулетовна', 1, 2023, 'active'),
(5, 'Нурлан Дамир Бекжанович', 1, 2023, 'active'),
(6, 'Сейтжан Маржан Нурлановна', 2, 2022, 'active'),
(7, 'Тлеубаев Санжар Ахметович', 2, 2022, 'active'),
(8, 'Ахметова Зарина Болатовна', 2, 2022, 'active'),
(9, 'Досмагамбетов Ерлан Нурович', 2, 2022, 'active'),
(10, 'Қалиева Гүлнәр Маратқызы', 3, 2023, 'active'),
(11, 'Серікбаев Тимур Асқарович', 3, 2023, 'active'),
(12, 'Бейсенова Айнур Жақсыбекқызы', 3, 2023, 'active'),
(13, 'Мустафин Руслан Болатович', 4, 2022, 'active'),
(14, 'Жаксыбекова Алия Сериковна', 4, 2022, 'active'),
(15, 'Нурмагамбетов Алибек Дүйсенович', 5, 2023, 'active');

-- --------------------------------------------------------

--
-- Структура таблицы `subjects`
--

CREATE TABLE `subjects` (
  `id` int NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `subjects`
--

INSERT INTO `subjects` (`id`, `name`) VALUES
(1, 'Математика'),
(2, 'Информатика'),
(3, 'Казахский язык'),
(4, 'Русский язык'),
(5, 'История Казахстана'),
(6, 'Алгоритмизация и программирование'),
(7, 'Базы данных'),
(8, 'Web-программирование'),
(9, 'Экономика'),
(10, 'Право');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Индексы таблицы `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Индексы таблицы `graduates`
--
ALTER TABLE `graduates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`);

--
-- Индексы таблицы `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT для таблицы `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `graduates`
--
ALTER TABLE `graduates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `students`
--
ALTER TABLE `students`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);

--
-- Ограничения внешнего ключа таблицы `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);

--
-- Ограничения внешнего ключа таблицы `graduates`
--
ALTER TABLE `graduates`
  ADD CONSTRAINT `graduates_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Ограничения внешнего ключа таблицы `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
