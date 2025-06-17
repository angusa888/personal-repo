I inputted the constitution text and formatted it into distinct divs with paragraph tags for the text and h2 / h4 tags for headings and subheadings respectively. One thing I learned in trying to format the text for the constitution is that you can't separate lines by simply using one <br/> statement. One <br/>will move the cursor to the next line, but it won't create an empty line to divide chunks of text. You need two <br/> statements to create a gap in the text, and in our case we also needed a &emsp to tab in for the newly created paragraph.

I also created the VM for our group and linked it to our team GitHub repo. As part of this I also had to create credentials for both of the TAs and figure out how to share the VM with the rest of my group as well. I then linked the VM to our team repo and used git pull commands to update the VM when our team made changes. Additionally, I secured our team site using certbot.

I also ran into a merge conflict with one of my branches and main, and I ended up resolving it by just copying my changes onto a new branch off of main and creating a new pull request. I would've tried to solve it using GitHub desktop diffs, but for some reason there was a version mismatch not allowing me to select the changes I wanted to pull.

I wrote the analysis for Article 1 and Amendments 1-6. I don't really like history or analysis so this part was not a whole lot of fun for me, but I got it done.

One other challenge I came across was trying to make sure the list items on the sources apge wouldn't overlap and all be on the same line. I ended up fixing this by setting the clear property of each list item to "both" that way only that one list item would appear on the line, for each list item. 

Resources I used:
- https://www.reaganlibrary.gov/constitutional-amendments-amendment-17-direct-election-senators
- https://www.uscourts.gov/about-federal-courts/court-role-and-structure
- https://www.history.com/news/third-amendment-constitution-james-madison