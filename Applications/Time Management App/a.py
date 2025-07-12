import tkinter as tk
from tkinter import ttk
 

LARGEFONT =("Verdana", 35)
 
class tkinterApp(tk.Tk):
    
    # __init__ function for class tkinterApp 
    def __init__(self, *args, **kwargs): 
        
        # __init__ function for class Tk
        tk.Tk.__init__(self, *args, **kwargs)
        
        # creating a container
        container = tk.Frame(self)  
        container.pack(side = "top", fill = "both", expand = True) 
 
        container.grid_rowconfigure(0, weight = 1)
        container.grid_columnconfigure(0, weight = 1)
 
        # initializing frames to an empty array
        self.frames = {}  
 
        # iterating through a tuple consisting
        # of the different page layouts
        for F in (calendarPage, homeworkPage, healthPage, timeManagementPage):
 
            frame = F(container, self)
 
            # initializing frame of that object from
            # calendarPage, homeworkPage, healthPage respectively with 
            # for loop
            self.frames[F] = frame 
 
            frame.grid(row = 0, column = 0, sticky ="nsew")
 
        self.show_frame(calendarPage)
 
    # to display the current frame passed as
    # parameter
    def show_frame(self, cont):
        frame = self.frames[cont]
        frame.tkraise()
 
# first window frame calendarPage
 
class calendarPage(tk.Frame):
    def __init__(self, parent, controller): 
        tk.Frame.__init__(self, parent)
        
        # label of frame Layout 2
        label = ttk.Label(self, text ="calendarPage", font = LARGEFONT)
        
        # putting the grid in its place by using
        # grid
        label.grid(row = 0, column = 4, padx = 10, pady = 10) 
 
        button1 = ttk.Button(self, text ="Page 1",
        command = lambda : controller.show_frame(homeworkPage))
    
        # putting the button in its place by
        # using grid
        button1.grid(row = 1, column = 1, padx = 10, pady = 10)
 
        ## button to show frame 2 with text layout2
        button2 = ttk.Button(self, text ="Page 2",
        command = lambda : controller.show_frame(healthPage))
    
        # putting the button in its place by
        # using grid
        button2.grid(row = 2, column = 1, padx = 10, pady = 10)
 
         
 
 
# second window frame homeworkPage 
class homeworkPage(tk.Frame):
    
    def __init__(self, parent, controller):
        
        tk.Frame.__init__(self, parent)
        label = ttk.Label(self, text ="Page 1", font = LARGEFONT)
        label.grid(row = 0, column = 4, padx = 10, pady = 10)
 
        # button to show frame 2 with text
        # layout2
        button1 = ttk.Button(self, text ="calendarPage",
                            command = lambda : controller.show_frame(calendarPage))
    
        # putting the button in its place 
        # by using grid
        button1.grid(row = 1, column = 1, padx = 10, pady = 10)
 
        # button to show frame 2 with text
        # layout2
        button2 = ttk.Button(self, text ="Page 2",
                            command = lambda : controller.show_frame(healthPage))
    
        # putting the button in its place by 
        # using grid
        button2.grid(row = 2, column = 1, padx = 10, pady = 10)
 
 
 
 
# third window frame healthPage
class healthPage(tk.Frame): 
    def __init__(self, parent, controller):
        tk.Frame.__init__(self, parent)
        label = ttk.Label(self, text ="Page 2", font = LARGEFONT)
        label.grid(row = 0, column = 4, padx = 10, pady = 10)
 
        # button to show frame 2 with text
        # layout2
        button1 = ttk.Button(self, text ="Page 1",
                            command = lambda : controller.show_frame(homeworkPage))
    
        # putting the button in its place by 
        # using grid
        button1.grid(row = 1, column = 1, padx = 10, pady = 10)
 
        # button to show frame 3 with text
        # layout3
        button2 = ttk.Button(self, text ="calendarPage",
                            command = lambda : controller.show_frame(calendarPage))
    
        # putting the button in its place by
        # using grid
        button2.grid(row = 2, column = 1, padx = 10, pady = 10)
class timeManagementPage(tk.Frame):
    def __init__(self, parent, controller):
        tk.Frame.__init__(self, parent)
        label = ttk.Label(self, text ="Page 3", font = LARGEFONT)
        label.grid(row = 0, column = 4, padx = 10, pady = 10)

        # button to show frame 2 with text
        # layout2
        button3 = ttk.Button(self, text ="Page 1",
                        command = lambda : controller.show_frame(homeworkPage))

        # putting the button in its place by 
        # using grid
        button3.grid(row = 1, column = 1, padx = 10, pady = 10)

        # button to show frame 3 with text
        # layout3
        button3 = ttk.Button(self, text ="calendarPage",
                        command = lambda : controller.show_frame(calendarPage))

        # putting the button in its place by
        # using grid
        button3.grid(row = 2, column = 1, padx = 10, pady = 10)
 
# Driver Code
app = tkinterApp()
app.mainloop()