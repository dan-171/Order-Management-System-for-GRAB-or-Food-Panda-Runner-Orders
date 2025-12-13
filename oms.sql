--
-- Indexes for table orders
--
ALTER TABLE orders
  ADD PRIMARY KEY (ID),
  ADD KEY member_id (Member_ID),
  ADD KEY runner_id (Runner_ID);

--
-- Indexes for table order_items
--
ALTER TABLE order_items
  ADD PRIMARY KEY (ID),
  ADD KEY order_id (Order_ID),
  ADD KEY item_id (Item_ID);

--
-- Indexes for table runners
--
ALTER TABLE runners
  ADD PRIMARY KEY (ID);

--
-- Indexes for table staff
--
ALTER TABLE staff
  ADD PRIMARY KEY (ID);

--
-- Constraints for dumped tables
--

--
-- Constraints for table orders
--
ALTER TABLE orders
  ADD CONSTRAINT member_id FOREIGN KEY (Member_ID) REFERENCES members (ID) ON DELETE CASCADE,
  ADD CONSTRAINT runner_id FOREIGN KEY (Runner_ID) REFERENCES runners (ID) ON DELETE CASCADE;

--
-- Constraints for table order_items
--
ALTER TABLE order_items
  ADD CONSTRAINT item_id FOREIGN KEY (Item_ID) REFERENCES items (ID) ON DELETE CASCADE,
  ADD CONSTRAINT order_id FOREIGN KEY (Order_ID) REFERENCES orders (ID) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
