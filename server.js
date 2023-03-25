//import express module
const exp=require("express")

//import dotenv
require("dotenv").config()

//call exp constructor
const app=exp()

//create a server
app.listen(4000,()=>console.log("Listening to port 4000"))






//Import IPRange Protocol
var iprange = require('iprange');
 


//Running shell commands in nodejs

const subProcess = require('child_process')

//route for refresh
app.get("/refresh",async (req,res)=>{
  console.log("Working");
    let result2=[]

    let finalResult=[]

    let ipsAsString=""


    let count=0
    //finding all ips in a network
    var range = iprange('192.168.0.0/24');

    //calculating total IPs
    totalIps=range.length
    console.log(totalIps)

    //Iterating through IPs
    for(ip of range)
    {
        //Executing ping command for each IP
        subProcess.exec("ping "+ip,(err, stdout, stderr)=>{
                count=count+1
                if(count==totalIps)
                {
                    //Get MAC address of all connected devices
                    subProcess.exec('arp -a', (err, stdout, stderr) => {
                    if (stderr) {
                      console.error(err)
                      process.exit(1)
                    } else {

                      let result=stdout.toString()
                      let entries=result.split("\r\n")

                      for(entry of entries){
                        let temp=entry.split("      ")
                        result2.push(temp[1])
                      }
                       for(mixedMac of result2)
                       {
                        let newstr=""
                          if(mixedMac==null)
                          continue
                          else{
                            let flag=0
                            for(char of mixedMac)
                            {
                              if(char==" " &&flag==1)
                              break
                              else if(char==" ")
                              continue
                              else{
                                  newstr=newstr+char
                                  flag=1
                              }
                            }
                          }
                          if(newstr=="Physical" || newstr=="" || newstr=="ff-ff-ff-ff-ff-ff")
                          continue
                          newstr+=","
                          ipsAsString+=newstr
                          finalResult.push(newstr)
                       }

                      //Clearing all ARP requests
                      subProcess.exec('arp -d', (err, stdout, stderr) => {
                        if (stderr) {
                          console.error(err)
                          process.exit(1)
                        } else {
                          console.log(`ARP entries cleared sucessfully`)
                        }
                      })

                      //Sending response
                      
                      res.send(ipsAsString)
                    }
                  })
                  
                }
        })
    }
})


//Invalid path middleware
app.use("*",(req,res)=>{
    res.send({message:"Invalid path"})
})

//Error handler middleware
app.use((err,req,res,next)=>{
    res.send({message:"Error occured ",error:err.message})
})